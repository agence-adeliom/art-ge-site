import React from 'react';
import {createBrowserRouter, RouterProvider,} from "react-router-dom";
import Home from '@screens/Home'
import Form from '@screens/Form'
import Information from '@screens/Informations'


export enum RoutePaths {
    HOME = '/',
    FORM = '/form',
    INFO = '/informations'
  }

export const routes = createBrowserRouter([
    {
        path: RoutePaths.HOME,
        element: (
            <Home></Home>
        ),
    },
    {
        path: RoutePaths.FORM,
        element: (
            <Form questions={[]}></Form>
        ),
    },
    {
        path: RoutePaths.INFO,
        element: (
            <Information></Information>
        ),
    },
  ]);

  
