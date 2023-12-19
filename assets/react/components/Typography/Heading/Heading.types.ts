import { ComponentProps } from 'react';
import { VariantProps } from 'class-variance-authority';
import { headingStyles } from './Heading.styles';

export const HeadingTags = ['h1', 'h2', 'h3', 'h4', 'h5', 'div'] as const;

export type HeadingTag = typeof HeadingTags[number];

export const HeadingTagVariants: {
  [key in HeadingTag]: VariantProps<typeof headingStyles>['variant'];
} = {
  h1: 'display-1',
  h2: 'display-2',
  h3: 'display-3',
  h4: 'display-4',
  h5: 'display-5',
  div: 'display-2',
};

export interface HeadingProps
  extends Omit<ComponentProps<'h1'>, 'color'>,
    Omit<ComponentProps<'div'>, 'color'>,
    VariantProps<typeof headingStyles> {
  as?: HeadingTag;
  raw?: boolean;
}
