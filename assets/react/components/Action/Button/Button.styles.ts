import { cva } from 'class-variance-authority';
import { weight } from '@components/Typography/Typography.styles';

export const buttonStyles = cva(
  [
    'relative flex items-center w-fit h-fit gap-4 transition-colors text-center',
  ],
  {
    variants: {
      variant: {
        primary: '',
        secondary: '',
        tertiary: '',
        textOnly: ''
      },
      size: {
        sm: 'text-xs py-1 px-2',
        md: 'text-sm py-3 px-5',
        lg: 'text-base py-4 px-5',
      },
      iconSide: {
        left: 'flex-row-reverse',
        right: '',
      },
      disabled: {
        true: 'disabled cursor-not-allowed',
      },
      loading: {
        true: 'cursor-wait',
      },
      weight,
    },
    compoundVariants: [
      {
        variant: 'primary',
        disabled: true,
        className: 'bg-neutral-400 text-white',
      },
      {
        variant: 'secondary',
        disabled: true,
        className: [
          'border border-neutral-500 text-neutral-500',
        ],
      },
      {
        variant: 'tertiary',
        disabled: true,
        className: [
          'bg-neutral-400 text-white',
        ],
      },
      {
        variant: 'textOnly',
        disabled: true,
        className: [
          'text-neutral-400',
        ],
      },
      {
        variant: 'primary',
        disabled: false,
        className: [
          'bg-primary-600 text-white',
          'lg:hover:bg-primary-800',
        ],
      },
      
      {
        variant: 'secondary',
        disabled: false,
        className: [
          'border border-primary-600 text-primary-600',
          'lg:hover:border-primary-800 lg:hover:text-primary-800',
        ],
      },
      {
        variant: 'tertiary',
        disabled: false,
        className: [
          'background-tertiary-600 text-white',
          'lg:hover:background-tertiary-800',
        ],
      },
      {
        variant: 'textOnly',
        disabled: false,
        className: [
          'text-primary-600',
          'lg:hover:text-primary-800',
        ],
      },
      {
        disabled: false,
        loading: false,
        className: 'cursor-pointer',
      },
    ],
    defaultVariants: {
      variant: 'primary',
      size: 'md',
      disabled: false,
      loading: false,
      weight: 500,
    },
  }
);
