import React, { FunctionComponent, createContext } from 'react';
import { BrowserRouter as Router, RouterProvider } from 'react-router-dom';
import { routes } from '../config/routes';
import { ReponseDataProvider } from '@hooks/useReponseData';

const Root: FunctionComponent = () => {
  return (
    <>
      <ReponseDataProvider>
        <RouterProvider router={routes} />
      </ReponseDataProvider>
    </>
  );
};

export default Root;
