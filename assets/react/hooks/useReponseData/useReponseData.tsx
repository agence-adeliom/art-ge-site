import { RoutePaths } from '@react/config/routes';
import React, {
  FunctionComponent,
  createContext,
  useContext,
  useState,
  ReactNode,
  useEffect,
} from 'react';

const wizardKey = '_wizard';

interface ProgressionProps {
  step: number;
  path: RoutePaths.FORM | RoutePaths.INFO;
}

interface RepondantProps {
  email?: string;
  firstname?: string;
  lastname?: string;
  phone?: string;
  legal?: boolean;
  company?: string;
  address?: string;
  city?: string;
  zip?: string;
  country?: string;
  restauration?: number;
  greenSpace?: number;
  departement?: number;
  typologie?: number;
}

interface RawFormProps {
  [key: number]: number[];
}

interface WIzardProps {
  progression?: ProgressionProps;
  repondant?: RepondantProps;
  rawForm?: RawFormProps;
}

const ReponseDataContext = createContext<{
  reponse: WIzardProps;
  setReponse: (reponse: WIzardProps) => void;
  getStoredReponse: () => WIzardProps | null;
  feedRepondant: (repondant: RepondantProps) => void;
  feedRawForm: (rawForm: RawFormProps) => void;
}>({
  reponse: {},
  setReponse: () => {},
  getStoredReponse: () => null,
  feedRepondant: () => {},
  feedRawForm: () => {},
});

export const ReponseDataProvider: FunctionComponent<{
  children: ReactNode;
}> = ({ children }) => {
  const [reponse, setReponse] = useState<WIzardProps>({});

  /* 
  Fonction alimentant l'objet repondant
  repondant est un objet de type RepondantProps et qui va être merge avec l'objet repondant actuel
  */
  const feedRepondant = (repondant: RepondantProps) => {
    setReponse({
      repondant: {
        ...reponse.repondant,
        ...repondant,
      },
    });
  };

  /* 
  Fonction alimentant l'objet rawForm
  rawForm est un objet de type RawFormProps et qui va être merge avec l'objet rawForm actuel
  */
  const feedRawForm = (rawForm: RawFormProps) => {
    setReponse({
      repondant: {
        ...reponse.repondant,
      },
      rawForm: {
        ...reponse.rawForm,
        ...rawForm,
      },
    });
  };

  const getStoredReponse = (): WIzardProps | null => {
    const localStorageValues = localStorage.getItem(wizardKey);
    if (localStorageValues) return JSON.parse(localStorageValues);
    return null;
  };

  return (
    <ReponseDataContext.Provider
      value={{
        reponse,
        setReponse,
        getStoredReponse,
        feedRepondant,
        feedRawForm,
      }}
    >
      {children}
    </ReponseDataContext.Provider>
  );
};

const useReponseData = () => useContext(ReponseDataContext);

export default useReponseData;

export const useWizard = () => {
  const [reponse, setReponse] = useState<WIzardProps>({});

  /* 
  Fonction alimentant l'objet repondant
  repondant est un objet de type RepondantProps et qui va être merge avec l'objet repondant actuel
  */
  const feedRepondant = (repondant: RepondantProps) => {
    setReponse({
      repondant: {
        ...reponse.repondant,
        ...repondant,
      },
    });
  };

  /* 
  Fonction alimentant l'objet rawForm
  rawForm est un objet de type RawFormProps et qui va être merge avec l'objet rawForm actuel
  */
  const feedRawForm = (rawForm: RawFormProps) => {
    const wizard = setReponse({
      repondant: {
        ...reponse.repondant,
      },
      rawForm: {
        ...reponse.rawForm,
        ...rawForm,
      },
    });
    localStorage.setItem(wizardKey, JSON.stringify(wizard));
  };

  const getStoredReponse = (): WIzardProps | null => {
    const localStorageValues = localStorage.getItem(wizardKey);
    if (localStorageValues) return JSON.parse(localStorageValues);
    return null;
  };

  return {
    reponse,
    getStoredReponse,
    feedRepondant,
    feedRawForm,
  };
};
