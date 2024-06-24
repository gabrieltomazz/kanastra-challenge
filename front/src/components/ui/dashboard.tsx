import { ReactElement } from "react";
import { FileProvider, useFileContext } from "./file";
import { FileUploader } from "./file-uploader";
import { Table, TableHeader, TableBody, TableRow, TableCell, TableHead } from "./table";

const FileListTable = () => {
    const { state } = useFileContext();
  
    return (
        <Table className="w-full mt-6 border-collapse">
        <TableHeader className="bg-gray-200">
          <TableRow>
            <TableHead className="px-4 py-2 border">Name</TableHead>
            <TableHead className="px-4 py-2 border">Status</TableHead>
            <TableHead className="px-4 py-2 border">Date Created</TableHead>
            <TableHead className="px-4 py-2 border">Processing Time</TableHead>

          </TableRow>
        </TableHeader>
        <TableBody>
          {state.fileList.map((file, index) => (
            <TableRow key={index} className="odd:bg-white even:bg-gray-100">
              <TableCell className="px-4 py-2 border">{file.name}</TableCell>
              <TableCell className="px-4 py-2 border">{file.status}</TableCell>
              <TableCell className="px-4 py-2 border">{file.created}</TableCell>
              <TableCell className="px-4 py-2 border">{file.processing_time} minutes</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    );
  };

function Dashboard(): ReactElement {
    return (
        <FileProvider>
            <div>
                <h1>Upload and List CSV Files</h1>
                <FileUploader />
                <FileListTable />
            </div>
        </FileProvider>
    );
}

export { Dashboard }
