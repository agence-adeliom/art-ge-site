import { ComponentProps } from 'react';
import { VariantProps } from 'class-variance-authority';
import { iconStyles } from '@components/Typography/Icon/Icon.styles';
import { IconProp } from '@fortawesome/fontawesome-svg-core';

export interface IconProps
  extends Omit<ComponentProps<'i' | 'div'>, 'color'>,
    VariantProps<typeof iconStyles> {
  icon: IconProp;
}

export type IconVariants = VariantProps<typeof iconStyles>['variant'];
