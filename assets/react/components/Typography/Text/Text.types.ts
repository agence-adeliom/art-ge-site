import { ComponentProps } from 'react';
import { VariantProps } from 'class-variance-authority';
import { textStyles } from '@components/Typography/Text/Text.styles';

export interface TextProps
  extends Omit<ComponentProps<'p'>, 'color'>,
    Omit<ComponentProps<'div'>, 'color'>,
    VariantProps<typeof textStyles> {
  as?: 'p' | 'div' | 'span';
}
