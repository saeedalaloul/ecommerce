<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use App\Notifications\VendorCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class VendorsController extends Controller
{
    public function index()
    {
        $vendors = Vendor::selection()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        $categories = MainCategory::where('translation_of', 0)->active()->get();
        return view('admin.vendors.create', compact('categories'));
    }

    public function store(VendorRequest $request)
    {
        try {

            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            $filePath = "";
            if ($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->logo);
            }

            $vendor = Vendor::create(
                [
                    'name' => $request->name,
                    'mobile' => $request->mobile,
                    'email' => $request->email,
                    'active' => $request->active,
                    'address' => $request->address,
                    'password' => $request->password,
                    'category_id' => $request->category_id,
                    'logo' => $filePath
                ]
            );

            Notification::send($vendor, new VendorCreated($vendor));

            return redirect()->route('admin.vendors')->with(['success' => 'تم حفظ المتجر بنجاح.']);
        } catch (\Exception $exception) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما يرجى المحاولة فيما بعد .']);
        }
    }

    public function edit($id)
    {
        try {
            $vendor = Vendor::Selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود .']);

            $categories = MainCategory::where('translation_of', 0)->active()->get();
            return view('admin.vendors.edit', compact('vendor', 'categories'));
        } catch (\Exception $exception) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما يرجى المحاولة فيما بعد .']);
        }
    }

    public function update($id, VendorRequest $request)
    {
        try {

            $vendor = Vendor::Selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود .']);

            DB::beginTransaction();
            if ($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->logo);
                Vendor::where('id', $id)->update([
                    'logo' => $filePath,
                ]);
            }

            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            $data = $request->except('_token', 'id', 'logo', 'password', 'latitude', 'longitude');
            if ($request->has('password')) {
                $data['password'] = $request->password;
            }

            Vendor::where('id', $id)->update($data);
            DB::commit();
            return redirect()->route('admin.vendors')->with(['success' => 'تم تحديث المتجر بنجاح.']);
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما يرجى المحاولة فيما بعد .']);
        }
    }


    public function destroy($id)
    {
        try {
            $vendor = Vendor::find($id);
            if (!$vendor) {
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود .']);
            }

            $image = Str::after($vendor->logo, 'assets/');
            $image = base_path('assets/' . $image);
            unlink($image);
            $vendor->delete();
            return redirect()->route('admin.vendors')->with(['success' => 'تم حذف المتجر بنجاح.']);
        } catch (\Exception $exception) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما يرجى المحاولة فيما بعد .']);
        }
    }

    public function changeStatus($id)
    {
        try {
            $vendor = Vendor::find($id);
            if (!$vendor) {
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود .']);
            }
            $status = $vendor->active == 0 ? 1 : 0;
            $vendor->update(['active' => $status]);
            if ($status == 0)
                return redirect()->route('admin.vendors')->with(['success' => 'تم إلغاء تفعيل المتجر بنجاح .']);
            else
                return redirect()->route('admin.vendors')->with(['success' => 'تم تفعيل المتجر بنجاح .']);
        } catch (\Exception $exception) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما يرجى المحاولة فيما بعد .']);
        }
    }


}
