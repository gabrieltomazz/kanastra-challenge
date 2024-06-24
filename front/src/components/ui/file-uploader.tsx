// file-uploader.tsx
import React, { useState } from "react";
import { useFileContext, FileActionType } from "./file";
import { uploadFile } from "../../services/fileService";

type FileUploaderProps = {};

const FileUploader = ({}: FileUploaderProps) => {
  const { dispatch } = useFileContext();
  const [file, setFile] = useState<File | null>(null);

  const handleFileChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const selectedFile = event.target.files?.[0] || null;
    setFile(selectedFile);
  };

  const handleUpload = async () => {
    if (file) {
      dispatch({ type: FileActionType.SET_LOADING });
      try {
        await uploadFile(file);
        dispatch({ type: FileActionType.UPLOAD_FILE, payload: { file } });
      } catch (error) {
        dispatch({ type: FileActionType.SET_ERROR, payload: { error: error.message } });
      } finally {
        setFile(null);
      }
    }
  };

  return (
    <div className="flex flex-col gap-6">
      <div>
        <label htmlFor="file" className="sr-only">
          Choose a file
        </label>
        <input
          id="file"
          type="file"
          accept=".csv"
          onChange={handleFileChange}
          className="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
        />
      </div>
      {file && (
        <section className="p-4 border rounded-lg bg-gray-50">
          <p className="pb-2 font-medium">File details:</p>
          <ul className="list-disc list-inside">
            <li><strong>Name:</strong> {file.name}</li>
            <li><strong>Type:</strong> {file.type}</li>
            <li><strong>Size:</strong> {file.size} bytes</li>
          </ul>
        </section>
      )}

      {file && (
        <button
          className="rounded-lg bg-green-800 text-white px-4 py-2 border-none font-semibold mt-4"
          onClick={handleUpload}
        >
          Upload the file
        </button>
      )}
    </div>
  );
};

export { FileUploader };
