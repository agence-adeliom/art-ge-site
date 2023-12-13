import React, {
  FunctionComponent,
  createContext,
  useContext,
  useState,
  ReactNode,
  useEffect,
} from 'react';

const UserProgressionContext = createContext<{
  step: number;
  setStep: (step: number) => void;
  prevStep: () => void;
  nextStep: () => void;
}>({
  step: 1,
  setStep: () => {},
  prevStep: () => {},
  nextStep: () => {},
});

export const UserProgressionProvider: FunctionComponent<{
  children: ReactNode;
}> = ({ children }) => {
  const [step, setStep] = useState<number>(1);

  const prevStep = () => {
    setStep(step => step - 1);
  };

  const nextStep = () => {
    setStep(step => step + 1);
  };

  return (
    <UserProgressionContext.Provider
      value={{ step, setStep, prevStep, nextStep }}
    >
      {children}
    </UserProgressionContext.Provider>
  );
};

const useProgression = () => useContext(UserProgressionContext);

export default useProgression;
