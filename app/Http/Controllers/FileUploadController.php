<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\FileUpload;


use Illuminate\Support\Facades\Storage;



class FileUploadController extends Controller
{







    public function index() 
    {
        $fileUplaods = FileUpload::get();
        return view('faculty/fileupload', ['fileUploads' => $fileUplaods]);
    }

    public function tosindex() 
    {
        $fileUplaods = FileUpload::get();
        return view('faculty/tos', ['fileUploads' => $fileUplaods]);
    }

    public function multipleUpload(Request $request) 
    {
        $messages = [
            'fileuploads.required' => 'Please upload at least one file.',
            'fileuploads.*.mimes' => 'The file type :attribute is not supported. Please upload one of the following types: doc, docx, jpg, jpeg, png, gif, pdf, ppt, pptx.',
        ];
    
        $customAttributes = [
            'fileuploads.*' => 'file',
        ];
    
        $validatedData = $request->validate([
            'fileuploads' => 'required',
            'fileuploads.*' => 'mimes:doc,docx,jpg,jpeg,png,gif,pdf,ppt,pptx'
        ], $messages, $customAttributes);
    
        $files = $request->file('fileuploads');
        foreach($files as $file){
            $fileUpload = new FileUpload;
            $fileUpload->filename = $file->getClientOriginalName();
            $fileUpload->filepath = $file->store('file_uploads');
            $fileUpload->type= $file->getClientOriginalExtension();
            $fileUpload->save();
        }   
        return redirect()->route('faculty/fileupload')->with('success', 'Files uploaded successfully!');
    }
    


    public function destroy(Request $request)
    {
        $fileUpload = FileUpload::find($request->input('file_id'));

        if ($fileUpload) {
            // Delete the file from storage
            Storage::delete($fileUpload->filepath);
    
            // Delete the file record from the database
            $fileUpload->delete();
    
            return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'File not found.']);
        }
    }
    
}