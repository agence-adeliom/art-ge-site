import React, { FunctionComponent, createContext } from 'react';
import { BrowserRouter as Router, RouterProvider } from 'react-router-dom';
import { routes } from '../config/routes';
import { WizardProvider } from '@hooks/useWizard';
const Root: FunctionComponent = () => {
  return (
    <>
      <WizardProvider>
        <RouterProvider router={routes} />
      </WizardProvider>
    </>
  );
};

export default Root;
