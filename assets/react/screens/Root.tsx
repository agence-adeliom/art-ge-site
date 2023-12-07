import React from 'react';
import { BrowserRouter as Router, RouterProvider } from 'react-router-dom';
import Home from '@screens/Home';
import Informations from '@screens/Informations';
import { useRoutes } from 'react-router-dom';
import { routes } from '../config/routes';

function App() {
  //const element = useRoutes(routes);
  return (
    <>
       <RouterProvider router={routes} />
    </>
  );
}

export default App;
