import React from 'react';
import { createRoot } from 'react-dom/client';
import '../styles/app.pcss';
import Home from '@screens/Home';
import Root from '@screens/Root';

 
const app = document.getElementById('app') as HTMLDivElement;
if (app) {
    const root = createRoot(app);
    root.render(
        <>             
            <Root/> 
        </>
    )
        
   
}
