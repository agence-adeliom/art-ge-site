import { ComponentPropsWithoutRef } from 'react';
import { VariantProps } from 'class-variance-authority';
import { buttonStyles } from '@components/Action/Button/Button.styles';
import { IconVariants } from '@components/Typography/Icon/Icon.types';
import { IconProp } from '@fortawesome/fontawesome-svg-core';

type ButtonOrLinkType = ComponentPropsWithoutRef<'button'> &
  ComponentPropsWithoutRef<'a'>;

interface ButtonOrLinkProps extends ButtonOrLinkType {}

export interface ButtonProps
  extends ButtonOrLinkProps,
    Omit<VariantProps<typeof buttonStyles>, 'disabled' | 'accent'> {
  icon?: IconProp;
  iconSide?: 'left' | 'right';
  loading?: boolean;
}
