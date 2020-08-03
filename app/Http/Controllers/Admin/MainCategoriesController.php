<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\MainCategory;
use Illuminate\Support\Facades\DB;

class MainCategoriesController extends Controller
{

    public function index()
    {
        $default_lang = get_default_language();
        $categories = MainCategory::where('translation_lang', $default_lang)->selection()->get();

        return view('admin.maincategories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.maincategories.create');
    }

    public function store(MainCategoryRequest $request)
    {
        try {
            $main_categories = collect($request->category);
            $filter = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] == get_default_language();
            });

            $default_category = array_values($filter->all())[0];
            $filePath = "";
            if ($request->has('photo')) {
                $filePath = uploadImage('maincategories', $request->photo);
            }
            DB::beginTransaction();
            $default_category_id = MainCategory::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'photo' => $filePath
            ]);

            $categories = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] != get_default_language();
            });

            if (isset($categories) && $categories->count() > 0) {
                $categories_arr = [];
                foreach ($categories as $category) {
                    $categories_arr[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $default_category_id,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'photo' => $filePath
                    ];
                }
                MainCategory::insert($categories_arr);
            }
            DB::commit();
            return redirect()->route('admin.maincategories')->with(['success' => 'تم حفظ القسم بنجاح.']);
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطأ ما يرجى المحاولة فيما بعد .']);
        }
    }

    public function edit($mainCat_id)
    {
        $mainCategory = MainCategory::with('categories')->selection()->find($mainCat_id);
        if (!$mainCategory) {
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود .']);
        }
        return view('admin.maincategories.edit', compact('mainCategory'));
    }

    public function update($mainCat_id, MainCategoryRequest $request)
    {
        try {
            $main_category = MainCategory::find($mainCat_id);
            if (!$main_category) {
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود .']);
            }

            $category = array_values($request->category)[0];


            if (!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            MainCategory::where('id', $mainCat_id)->update([
                'name' => $category['name'],
                'active' => $request->active,
            ]);

            if ($request->has('photo')) {
                $filePath = uploadImage('maincategories', $request->photo);
                MainCategory::where('id', $mainCat_id)->update([
                    'photo' => $filePath,
                ]);
            }

            return redirect()->route('admin.maincategories')->with(['success' => 'تم تحديث القسم بنجاح.']);

        } catch (\Exception $exception) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطأ ما يرجى المحاولة فيما بعد .']);
        }
    }

}
