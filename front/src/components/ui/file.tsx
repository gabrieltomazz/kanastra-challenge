import React, { ReactNode, createContext, useContext, useReducer, useEffect } from "react";
import { fetchFiles } from "../../services/fileService";

enum FileActionType {
  UPLOAD_FILE = "UPLOAD_FILE",
  SET_FILES = "SET_FILES",
  SET_LOADING = "SET_LOADING",
  SET_ERROR = "SET_ERROR",
}

type ReducerAction<T, P> = {
  type: T;
  payload?: Partial<P>;
};

type FileContextState = {
  isLoading: boolean;
  file: File | null;
  fileList: {
    name: string;
    type: string;
    size: number;
    status: string; 
    created: string;
    processing_time: string;
  }[];
  error: string | null;
};

type FileAction = ReducerAction<FileActionType, FileContextState>;

type FileDispatch = ({ type, payload }: FileAction) => void;

type FileContextType = {
  state: FileContextState;
  dispatch: FileDispatch;
};

type FileProviderProps = { children: ReactNode };

export const FileContextInitialValues: FileContextState = {
  file: null,
  isLoading: false,
  fileList: [],
  error: null,
};

const FileContext = createContext({} as FileContextType);

const FileReducer = (state: FileContextState, action: FileAction): FileContextState => {
  switch (action.type) {
    case FileActionType.UPLOAD_FILE:
      return {
        ...state,
        fileList: [...state.fileList, action.payload?.file!],
        isLoading: false,
      };
    case FileActionType.SET_FILES:
      return {
        ...state,
        fileList: action.payload?.fileList || [],
        isLoading: false,
      };
    case FileActionType.SET_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case FileActionType.SET_ERROR:
      return {
        ...state,
        error: action.payload?.error || null,
        isLoading: false,
      };
    default:
      throw new Error(`Unhandled action type: ${action.type}`);
  }
};

const FileProvider = ({ children }: FileProviderProps) => {
  const [state, dispatch] = useReducer(FileReducer, FileContextInitialValues);

  useEffect(() => {
    const fetchUploadedFiles = async () => {
      dispatch({ type: FileActionType.SET_LOADING });
      try {
        const files = await fetchFiles();
        dispatch({ type: FileActionType.SET_FILES, payload: { fileList: files } });
      } catch (error) {
        dispatch({ type: FileActionType.SET_ERROR, payload: { error: error.message } });
      }
    };

    fetchUploadedFiles();
  }, []);

  return (
    <FileContext.Provider value={{ state, dispatch }}>
      {children}
    </FileContext.Provider>
  );
};

const useFileContext = () => {
  const context = useContext(FileContext);

  if (context === undefined)
    throw new Error("useFileContext must be used within a FileProvider");

  return context;
};

export { FileProvider, useFileContext, FileActionType };
