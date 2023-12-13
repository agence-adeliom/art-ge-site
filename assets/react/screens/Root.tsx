import React, { FunctionComponent, createContext } from 'react';
import { BrowserRouter as Router, RouterProvider } from 'react-router-dom';
import { routes } from '../config/routes';
import { ReponseDataProvider } from '@hooks/useReponseData';
import { UserProgressionProvider } from '@hooks/useProgression';

const Root: FunctionComponent = () => {
  return (
    <>
      <UserProgressionProvider>
        <ReponseDataProvider>
          <RouterProvider router={routes} />
        </ReponseDataProvider>
      </UserProgressionProvider>
    </>
  );
};

export default Root;
