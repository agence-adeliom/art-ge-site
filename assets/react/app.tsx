import React from 'react';
import { createRoot } from 'react-dom/client';
import '../styles/app.pcss';
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
