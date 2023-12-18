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

interface StepProps {
  index: number;
  path: string;
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
  [key: string]: { answers: number[] };
}

interface WizardProps {
  step: StepProps;

  reponse?: {
    repondant?: RepondantProps;
    rawForm?: RawFormProps;
  };
}

const WizardContext = createContext<{
  wizard: WizardProps;
  clearWizard: () => void;
  getStoredWizard: () => WizardProps | null;
  feedRepondantAndGoToNextStep: (
    repondant: RepondantProps,
    stepPath?: string,
  ) => void;
  feedRawFormAndGoToNextStep: (rawForm: RawFormProps) => void;
  step: number;
  setStep: (step: number) => void;
  prevStep: () => void;
  resetStep: () => void;
}>({
  wizard: { step: { index: 1, path: '/informations' } },
  clearWizard: () => {},
  getStoredWizard: () => null,
  feedRepondantAndGoToNextStep: () => {},
  feedRawFormAndGoToNextStep: () => {},
  step: 1,
  setStep: () => {},
  prevStep: () => {},
  resetStep: () => {},
});

export const WizardProvider: FunctionComponent<{
  children: ReactNode;
}> = ({ children }) => {
  const [step, setStep] = useState<number>(1);
  const [ready, setReady] = useState<boolean>(false);

  const [wizard, setWizard] = useState<WizardProps>({
    step: { index: step, path: RoutePaths.INFO },
  });

  const prevStep = () => {
    setStep(step => step - 1);
  };

  const resetStep = () => {
    const updatedWizard = {
      step: {
        index: 0,
        path: RoutePaths.FORM,
      },
      reponse: {
        ...wizard?.reponse,
      },
    };
    setStep(0);
    setWizard(updatedWizard);
    localStorage.setItem(wizardKey, JSON.stringify(updatedWizard));
  };

  /* 
  Fonction alimentant l'objet repondant
  repondant est un objet de type RepondantProps et qui va être merge avec l'objet repondant actuel
  */
  const feedRepondantAndGoToNextStep = (
    repondant: RepondantProps,
    stepPath?: string,
  ) => {
    //Reset des données RawForm si le GreenSpace change pour éviter d'avoir de fausses données dans le wizard
    const shouldResetRawForm =
      repondant?.greenSpace !== wizard?.reponse?.repondant?.greenSpace;

    const updatedWizard: WizardProps = {
      step: {
        index: step + 1,
        path: stepPath ?? wizard.step.path,
      },
      reponse: {
        repondant: {
          ...wizard?.reponse?.repondant,
          ...repondant,
        },
        rawForm: shouldResetRawForm
          ? {}
          : {
              ...wizard?.reponse?.rawForm,
            },
      },
    };

    setStep(step => step + 1);
    setWizard(updatedWizard);
  };

  /* 
  Fonction alimentant l'objet rawForm
  rawForm est un objet de type RawFormProps et qui va être merge avec l'objet rawForm actuel
  */
  const feedRawFormAndGoToNextStep = (rawForm: RawFormProps) => {
    const updatedWizard: WizardProps = {
      step: {
        index: step + 1,
        path: wizard.step.path,
      },
      reponse: {
        repondant: {
          ...wizard?.reponse?.repondant,
        },
        rawForm: {
          ...wizard?.reponse?.rawForm,
          ...rawForm,
        },
      },
    };
    setStep(step => step + 1);
    setWizard(updatedWizard);
  };

  const getStoredWizard = (): WizardProps | null => {
    const localStorageValues = localStorage.getItem(wizardKey);
    if (localStorageValues) return JSON.parse(localStorageValues);
    return null;
  };

  const clearWizard = () => {
    localStorage.removeItem(wizardKey);
    setWizard({ step: { index: 1, path: RoutePaths.INFO } });
    setStep(1);
  };

  useEffect(() => {
    const storedWizard = getStoredWizard();
    if (storedWizard) {
      setWizard(storedWizard);
      setStep(storedWizard.step.index);
    }
    setReady(true);
  }, []);

  useEffect(() => {
    ready && localStorage.setItem(wizardKey, JSON.stringify(wizard));
  }, [wizard]);

  return (
    <WizardContext.Provider
      value={{
        wizard,
        clearWizard,
        getStoredWizard,
        feedRepondantAndGoToNextStep,
        feedRawFormAndGoToNextStep,
        step,
        setStep,
        prevStep,
        resetStep,
      }}
    >
      {children}
    </WizardContext.Provider>
  );
};

export const useWizard = () => useContext(WizardContext);
