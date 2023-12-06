import React from 'react';
import { Icon } from '@components/Typography/Icon';

export const Error = ({ message }: { message: string }) => {
  return (
    <div
      role="alert"
      className="mt-1.5 flex items-center gap-1.5 text-danger-600"
    >
      <Icon icon="fa-circle-info" className="flex-shrink-0" />

      <div className="text-sm">{message}</div>
    </div>
  );
};
