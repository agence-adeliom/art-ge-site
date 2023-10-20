import React from 'react';
import { createRoot } from 'react-dom/client';
import './styles/app.css';

const app = document.getElementById('app') as HTMLDivElement | null;
if (app) {
    const root = createRoot(app);
    root.render(<h1>Hello, from react typescript</h1>);
}
