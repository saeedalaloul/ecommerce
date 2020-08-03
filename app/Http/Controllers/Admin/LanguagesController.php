<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguagesRequest;
use App\Models\language;

class LanguagesController extends Controller
{
    public function index()
    {
        $langauges = language::select()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index', compact('langauges'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(LanguagesRequest $request)
    {
        try {
            if (!$request->has('active'))
                $request->request->add(['active' => 0]);

            language::create($request->except(['_token']));
            return redirect()->route('admin.languages')->with(['success' => 'تم حفظ اللغة بنجاح.']);
        } catch (\Exception $exception) {
            return redirect()->route('admin.languages')->with(['success' => 'هناك خطأ ما يرجى المحاولة فيما بعد.']);
        }
    }

    public function edit($id)
    {
        $language = language::select()->find($id);
        if (!$language) {
            return redirect()->route('admin.languages')->with(['error' => 'هذه اللغة غير موجودة.']);
        }
        return view('admin.languages.edit', compact('language'));
    }

    public function update($id, LanguagesRequest $request)
    {
        try {
            $lanauage = language::find($id);
            if (!$lanauage) {
                return redirect()->route('admin.languages.edit', $id)->with(['error' => 'هذه اللغة غير موجودة.']);
            }

            if (!$request->has('active'))
                $request->request->add(['active' => 0]);

            $lanauage->update($request->except('_token'));
            return redirect()->route('admin.languages')->with(['success' => 'تم تحديث اللغة بنجاح.']);
        } catch (\Exception $exception) {
            return redirect()->route('admin.languages')->with(['success' => 'هناك خطأ ما يرجى المحاولة فيما بعد.']);
        }
    }

    public function destroy($id)
    {
        try {
            $lanauage = language::find($id);
            if (!$lanauage) {
                return redirect()->route('admin.languages', $id)->with(['error' => 'هذه اللغة غير موجودة.']);
            }

            $lanauage->delete();
            return redirect()->route('admin.languages')->with(['success' => 'تم حذف اللغة بنجاح.']);
        } catch (\Exception $exception) {
            return redirect()->route('admin.languages')->with(['success' => 'هناك خطأ ما يرجى المحاولة فيما بعد.']);
        }
    }
}
