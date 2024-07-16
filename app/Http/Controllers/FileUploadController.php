<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\TableOfSpecification;
use Illuminate\Support\Facades\Auth;

class FileUploadController extends Controller
{
    public function index() 
    {
        $fileUploads = FileUpload::get();
        return view('faculty.fileupload', ['fileUploads' => $fileUploads]);
    }

    public function multipleUpload(Request $request) 
    {
        try {
            Log::info('Received files for upload', $request->all());

            $messages = [
                'fileuploads.required' => 'Please upload at least one file.',
                'fileuploads.*.required' => 'Please upload a file for topic :attribute.',
                'fileuploads.*.*.mimes' => 'The file type :attribute is not supported. Please upload one of the following types: doc, docx, jpg, jpeg, png, gif, pdf, ppt, pptx.',
            ];

            $customAttributes = [
                'fileuploads.*' => 'file',
            ];

            $request->validate([
                'fileuploads' => 'required|array',
                'fileuploads.*' => 'required|array',
                'fileuploads.*.*' => 'required|mimes:doc,docx,jpg,jpeg,png,gif,pdf,ppt,pptx'
            ], $messages, $customAttributes);

            foreach ($request->fileuploads as $topicId => $files) {
                Log::info("Processing topic ID: $topicId");

                $topic = TableOfSpecification::find($topicId);
                if ($topic) {
                    Log::info("Found topic: " . $topic->topic);

                    foreach ($files as $file) {
                        Log::info("Processing file: " . $file->getClientOriginalName());

                        if ($file->isValid()) {
                            $extension = $file->getClientOriginalExtension();
                            $filename = 'TOS_' . $topicId . '_' . time() . '.' . $extension;
                            $filepath = $file->storeAs('file_uploads', $filename);

                            Log::info("Stored file as: $filepath");

                            // Save file information to the database
                            $fileUpload = new FileUpload;
                            $fileUpload->tos_id = $topicId;
                            $fileUpload->filename = $filename;
                            $fileUpload->filepath = $filepath;
                            $fileUpload->type = $extension;
                            $fileUpload->save();

                            Log::info("File uploaded and saved: $filename");
                        } else {
                            Log::warning("File is not valid: " . $file->getClientOriginalName());
                            return response()->json(['success' => false, 'message' => 'One or more files are invalid. Please ensure all files are valid and try again.'], 422);
                        }
                    }
                } else {
                    Log::warning("Topic not found for ID: $topicId");
                    return response()->json(['success' => false, 'message' => 'Topic not found for ID: ' . $topicId], 404);
                }
            }

            return response()->json(['success' => true, 'message' => 'Files uploaded successfully!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            Log::error('Validation error during file upload: ' . implode(", ", $errors));
            return response()->json(['success' => false, 'message' => 'No file selected. ' . implode(", ", $errors)], 422);
        } catch (\Exception $e) {
            Log::error('Error during file upload: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred during file upload. Please try again.'], 500);
        }
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

    public function destroyMultiple(Request $request)
    {
        $fileIds = $request->input('file_ids');

        if (!$fileIds) {
            return response()->json(['success' => false, 'message' => 'No files selected.'], 400);
        }

        $fileUploads = FileUpload::whereIn('id', $fileIds)->get();

        foreach ($fileUploads as $fileUpload) {
            // Delete the file from storage
            Storage::delete($fileUpload->filepath);

            // Delete the file record from the database
            $fileUpload->delete();
        }

        return response()->json(['success' => true, 'message' => 'Files deleted successfully.']);
    }

    public function getRecentTopics()
    {
        $userId = Auth::id();

        // Get the latest TableOfSpecification entry for the current user
        $latestTOS = TableOfSpecification::where('user_id', $userId)->latest()->first();
        $createdAt = $latestTOS ? $latestTOS->created_at : null;

        // Filter TableOfSpecification entries based on the exact creation timestamp
        $recentTopics = TableOfSpecification::where('user_id', $userId)
            ->when($createdAt, function ($query, $createdAt) {
                return $query->where('created_at', $createdAt);
            })
            ->latest()
            ->get();

        return response()->json($recentTopics);
    }
}
