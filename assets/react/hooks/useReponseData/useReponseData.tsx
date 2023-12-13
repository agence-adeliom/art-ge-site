import React, {
  FunctionComponent,
  createContext,
  useContext,
  useState,
  ReactNode,
} from 'react';

interface RepondantProps {
  email?: string;
  firstname?: string;
  lastname?: string;
  phone?: string;
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

interface ReponseProps {
  repondant?: RepondantProps;
  rawForm?: RawFormProps;
}

const ReponseDataContext = createContext<{
  reponse: ReponseProps;
  setReponse: (reponse: ReponseProps) => void;
  feedRepondant: (repondant: RepondantProps) => void;
  feedRawForm: (rawForm: RawFormProps) => void;
}>({
  reponse: { repondant: {} },
  setReponse: () => {},
  feedRepondant: () => {},
  feedRawForm: () => {},
});

export const ReponseDataProvider: FunctionComponent<{
  children: ReactNode;
}> = ({ children }) => {
  const [reponse, setReponse] = useState<ReponseProps>({ repondant: {} });

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

  return (
    <ReponseDataContext.Provider
      value={{ reponse, setReponse, feedRepondant, feedRawForm }}
    >
      {children}
    </ReponseDataContext.Provider>
  );
};

const useReponseData = () => useContext(ReponseDataContext);

export default useReponseData;
