import axios from "axios";
const API_URL = "http://localhost"; 

const api = axios.create({
    baseURL: API_URL,
    headers: {
        "Content-Type": "multipart/form-data",
        "Access-Control-Allow-Origin": "*",
        "Access-Control-Allow-Headers": "Authorization",
        "Access-Control-Allow-Methods": "GET, POST",
    },
  });

// Function to upload a file
export const uploadFile = async (file: File): Promise<void> => {
  const formData = new FormData();
  formData.append("file", file);

  await api.post(`${API_URL}/upload-csv`, formData);
};

// Function to fetch the list of uploaded files
export const fetchFiles = async (): Promise<File[]> => {
  const response = await api.get(`${API_URL}/files`);
  return response.data;
};