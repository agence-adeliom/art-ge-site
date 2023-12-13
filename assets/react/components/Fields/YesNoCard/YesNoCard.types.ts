import React, { ReactNode, ComponentProps } from 'react';

export interface YesNoCardProps extends ComponentProps<'div'> {
  name: string;
  defaultValue?: number;
  control: any;
}
