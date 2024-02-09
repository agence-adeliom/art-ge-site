import { cva } from 'class-variance-authority';
import { weight } from '@components/Typography/Typography.styles';

export const buttonStyles = cva(
  [
    'max-md:justify-center relative w-full md:w-fit flex items-center w-fit h-fit gap-4 transition-colors text-center',
  ],
  {
    variants: {
      variant: {
        primary: '',
        secondary: '',
        tertiary: '',
        textOnly: '',
        resetFilter: ''
      },
      size: {
        sm: 'text-xs py-1 px-2',
        md: 'text-sm py-2 px-5',
        lg: 'text-base py-3 px-5',
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
          'bg-neutral-700 text-white',
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
          'bg-primary-600 text-white dark:bg-white dark:text-primary-600',
          'lg:hover:bg-primary-800 dark:lg:hover:text-white',
        ],
      },
      
      {
        variant: 'secondary',
        disabled: false,
        className: [
          'text-white border bg-neutral-700',
          'lg:hover:bg-black',
        ],
      },
      {
        variant: 'tertiary',
        disabled: false,
        className: [
          'bg-tertiary-600 text-white',
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
        variant: 'resetFilter',
        disabled: false,
        className: [
          'text-neutral-700',
          'lg:hover:text-black',
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
