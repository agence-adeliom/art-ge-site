import React, { ReactNode, ComponentProps } from 'react';

export interface ChoiceCardsProps extends ComponentProps<'div'> {
  isActive: boolean;
  icon: {
    iconSrc: string;
    alt: string;
  };
}
