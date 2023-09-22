import React from 'react';
import { createRoot } from 'react-dom/client';
import './styles/app.css';

const root = createRoot(document.getElementById('app') as HTMLDivElement);
root.render(<h1>Hello, from react typescript</h1>);
