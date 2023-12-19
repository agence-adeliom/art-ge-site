import { ComponentProps, MouseEvent } from 'react';
import {Choice} from "@screens/Resultats";

export interface AccordionProps extends ComponentProps<'div'> {
  question: string;
  choices: Choice[];
  isOpen: boolean;
  handleClick: (e: MouseEvent<HTMLElement>) => void;
}
