import { cva } from 'class-variance-authority';
import {
  weight,
  center,
  color,
} from '../Typography.styles';

export const headingStyles = cva('display font-title dark:text-white transition-colors', {
  variants: {
    variant: {
      'display-1': 'display-1 text-5xl md:text-6xl lg:text-7xl',
      'display-2': 'display-2 text-4xl md:text-5xl lg:text-6xl',
      'display-3': 'display-3 text-4xl lg:text-5xl',
      'display-4': 'display-4 text-3xl lg:text-4xl',
      'display-5': 'display-5 text-xl md:text-2xl lg:text-3xl',
      headline: 'headline text-sm uppercase tracking-[0.02em] font-bold',
      'headline-lg': 'headline text-base uppercase tracking-[0.02em] font-bold',
    },
    weight,
    center,
    color,
  },
  defaultVariants: {
    variant: 'display-1',
    color: 'black',
    weight: 500,
  },
});
