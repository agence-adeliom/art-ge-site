import React from 'react';
import { createRoot } from 'react-dom/client';
import '../styles/app.pcss';
import Root from '@screens/Root';

const rootElement = document.getElementById('root') as HTMLDivElement;
if (rootElement) {
  const root = createRoot(rootElement);
  root.render(
    <>
      <Root />
    </>,
  );
}
