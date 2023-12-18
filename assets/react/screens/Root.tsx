import React, { FunctionComponent, createContext } from 'react';
import { BrowserRouter as Router, RouterProvider } from 'react-router-dom';
import { routes } from '../config/routes';
import { WizardProvider } from '@hooks/useWizard';
import { UserProgressionProvider } from '@hooks/useProgression';

const Root: FunctionComponent = () => {
  return (
    <>
      <UserProgressionProvider>
        <WizardProvider>
          <RouterProvider router={routes} />
        </WizardProvider>
      </UserProgressionProvider>
    </>
  );
};

export default Root;
