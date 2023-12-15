import { ComponentProps, MouseEvent } from 'react';

export interface AccordionProps extends ComponentProps<'div'> {
  question?: string;
  answer?: Array<any>;
  isOpen: boolean;
  handleClick: (e: MouseEvent<HTMLElement>) => void;
}
