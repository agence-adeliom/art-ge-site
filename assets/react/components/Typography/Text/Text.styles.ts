import { cva } from 'class-variance-authority';
import {
  center,
  weight,
  color,
} from '@components/Typography/Typography.styles';

export const textStyles = cva('text transition-colors font-text dark:text-white', {
  variants: {
    size: {
      xs: 'text-xs',
      sm: 'text-sm',
      base: 'text-base',
      lg: 'text-lg',
      xl: 'text-xl',
      '2xl': 'text-2xl',
      '3xl': 'text-3xl',
      '4xl': 'text-4xl',
      '5xl': 'text-5xl',
      '6xl': 'text-6xl',
    },
    weight,
    center,
    color,
  },
  defaultVariants: {
    size: 'base',
    weight: 400,
    color: 'black',
  },
});
