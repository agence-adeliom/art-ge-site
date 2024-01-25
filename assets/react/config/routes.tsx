import React from 'react';
import { createBrowserRouter } from 'react-router-dom';
import Home from '@screens/Home';
import Form from '@screens/Form';
import Information from '@screens/Informations';
import Resultats from '@screens/Resultats';
import Territory from '@screens/Territory';

export enum RoutePaths {
  HOME = '/',
  FORM = '/form',
  INFO = '/informations',
  RESULT_ARCHIVE = '/resultat',
  RESULTATS = '/resultat/*',
  TERRITORY = '/territoire/*',
}

export const routes = createBrowserRouter([
  {
    path: RoutePaths.HOME,
    element: <Home></Home>,
  },
  {
    path: RoutePaths.FORM,
    element: <Form></Form>,
  },
  {
    path: RoutePaths.INFO,
    element: <Information></Information>,
  },
  {
    path: RoutePaths.RESULTATS,
    element: <Resultats></Resultats>,
  },
  {
    path: RoutePaths.TERRITORY,
    element: <Territory></Territory>,
  },
]);
