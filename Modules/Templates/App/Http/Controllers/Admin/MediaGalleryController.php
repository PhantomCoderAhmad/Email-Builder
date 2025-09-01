<?php

namespace Modules\Templates\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Templates\App\Models\MediaLibrary;

class MediaGalleryController extends Controller
{
    /**
     * Display a listing of the media library with pagination.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch media library items with pagination (10 items per page)
        $mediaLibrary = MediaLibrary::orderBy('created_at', 'desc')->paginate(100);
        return view('templates::tailwind.admin.media.gallery', compact('mediaLibrary'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        //Save file using Storage facade
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();
        $hashName = hash('md5', $fileName) . '.' . $file->getClientOriginalExtension();
        Storage::disk('media_library_public')->put('images/' . $hashName, file_get_contents($file));
        $media = MediaLibrary::create([
            'filename' => $fileName,
            'path' => 'images/' . $hashName,
        ]);
        if ($media) {
            return back()
                ->with('success', 'You have successfully uploaded image.');
        }
        return back()
            ->with('error', 'Failed to upload image');
    }

    public function destroy($id)
    {
        $media = MediaLibrary::find($id);
        if ($media) {
            Storage::disk('media_library_public')->delete('media-library/' . $media->path);
            $media->delete();
            return back()
                ->with('success', 'You have successfully deleted image.');
        }
        return back()
            ->with('error', 'Failed to delete image');
    }
}
