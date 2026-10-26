<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TobaccoUmkm;
use App\Models\District;
use App\Models\Category;
use Illuminate\Validation\Rule;

class UmkmController extends Controller
{
    /**
     * Tampilkan daftar seluruh UMKM dengan filter dan pagination
     */
    public function index(Request $request)
    {
        $districts = District::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $query = TobaccoUmkm::with(['district', 'category']);

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('business_status', $request->status);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('village', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%");
            });
        }

        $umkms = $query->latest()->paginate(15)->withQueryString();

        return view('admin.umkm.index', compact('umkms', 'districts', 'categories'));
    }

    /**
     * Tampilkan formulir tambah UMKM baru
     */
    public function create()
    {
        $districts = District::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.umkm.create', compact('districts', 'categories'));
    }

    /**
     * Simpan data UMKM baru ke basis data
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:tobacco_umkms,code'],
            'name' => ['required', 'string', 'max:200'],
            'owner_name' => ['nullable', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'address' => ['required', 'string'],
            'village' => ['nullable', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-7.3500000,-6.9500000'],
            'longitude' => ['required', 'numeric', 'between:103.3500000,103.6500000'],
            'land_area_ha' => ['nullable', 'numeric', 'min:0'],
            'production_capacity' => ['nullable', 'numeric', 'min:0'],
            'production_unit' => ['required', 'string', 'max:50'],
            'actual_production' => ['nullable', 'numeric', 'min:0'],
            'production_period' => ['required', 'string', 'max:50'],
            'worker_count' => ['nullable', 'integer', 'min:0'],
            'transaction_value' => ['nullable', 'numeric', 'min:0'],
            'business_status' => ['required', 'in:aktif,musiman,non-aktif'],
            'data_source' => ['required', 'string', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
        ], [
            'latitude.between' => 'Titik lintang harus berada dalam batas geografis Kabupaten Pamekasan (-7.3500000 s/d -6.9500000).',
            'longitude.between' => 'Titik bujur harus berada dalam batas geografis Kabupaten Pamekasan (103.3500000 s/d 103.6500000).',
            'code.unique' => 'Kode registrasi UMKM ini sudah digunakan.',
        ]);

        $validated['created_by'] = $request->user()?->id;
        $validated['is_verified'] = true;

        $umkm = TobaccoUmkm::create($validated);

        return redirect()->route('admin.umkm.index')
            ->with('success', "Data UMKM '{$umkm->name}' berhasil ditambahkan ke sistem.");
    }

    /**
     * Tampilkan detail data UMKM
     */
    public function show(TobaccoUmkm $umkm)
    {
        $umkm->load(['district', 'category', 'creator']);

        return view('admin.umkm.show', compact('umkm'));
    }

    /**
     * Tampilkan formulir edit UMKM
     */
    public function edit(TobaccoUmkm $umkm)
    {
        $districts = District::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.umkm.edit', compact('umkm', 'districts', 'categories'));
    }

    /**
     * Perbarui data UMKM di basis data
     */
    public function update(Request $request, TobaccoUmkm $umkm)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('tobacco_umkms', 'code')->ignore($umkm->id)],
            'name' => ['required', 'string', 'max:200'],
            'owner_name' => ['nullable', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'address' => ['required', 'string'],
            'village' => ['nullable', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-7.3500000,-6.9500000'],
            'longitude' => ['required', 'numeric', 'between:103.3500000,103.6500000'],
            'land_area_ha' => ['nullable', 'numeric', 'min:0'],
            'production_capacity' => ['nullable', 'numeric', 'min:0'],
            'production_unit' => ['required', 'string', 'max:50'],
            'actual_production' => ['nullable', 'numeric', 'min:0'],
            'production_period' => ['required', 'string', 'max:50'],
            'worker_count' => ['nullable', 'integer', 'min:0'],
            'transaction_value' => ['nullable', 'numeric', 'min:0'],
            'business_status' => ['required', 'in:aktif,musiman,non-aktif'],
            'data_source' => ['required', 'string', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
        ], [
            'latitude.between' => 'Titik lintang harus berada dalam batas geografis Kabupaten Pamekasan (-7.3500000 s/d -6.9500000).',
            'longitude.between' => 'Titik bujur harus berada dalam batas geografis Kabupaten Pamekasan (103.3500000 s/d 103.6500000).',
        ]);

        $umkm->update($validated);

        return redirect()->route('admin.umkm.index')
            ->with('success', "Pembaruan data UMKM '{$umkm->name}' berhasil disimpan.");
    }

    /**
     * Hapus data UMKM dari sistem
     */
    public function destroy(TobaccoUmkm $umkm)
    {
        $name = $umkm->name;
        $umkm->delete();

        return redirect()->route('admin.umkm.index')
            ->with('success', "Data UMKM '{$name}' telah dihapus dari sistem.");
    }
}
