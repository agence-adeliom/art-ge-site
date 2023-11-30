import { cva, VariantProps } from 'class-variance-authority';
import { color } from '@components/Typography/Typography.styles';

export const iconStyles = cva('', {
  variants: {
    variant: {
      regular: 'fa fa-regular',
      solid: 'fa-solid',
      duotone: 'fad',
      light: 'fal',
      thin: 'fat',
      brands: 'fab',
    },
    size: {
      xxs: 'text-[10px]',
      xs: 'text-xs',
      sm: 'text-sm',
      base: 'text-base',
      lg: 'text-lg',
      xl: 'text-xl',
      '2xl': 'text-2xl',
      '3xl': 'text-3xl',
      '4xl': 'text-4xl',
      '5xl': 'text-5xl',
    },
    color,
  },
  defaultVariants: {
    variant: 'regular',
    size: 'base',
    color: 'inherhit',
  },
});
