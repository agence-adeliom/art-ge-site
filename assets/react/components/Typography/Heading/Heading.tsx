import React, { FunctionComponent } from 'react';
import { cx } from 'class-variance-authority';
import { HeadingProps } from '@components/Typography/Heading/Heading.types';
import { headingStyles } from '@components/Typography/Heading/Heading.styles';

export const Heading: FunctionComponent<HeadingProps> = ({
  children,
  as: HeadingTag = 'div',
  variant,
  weight,
  center,
  color,
  className,
  ...props
}) => {
  return (
    <HeadingTag
      className={cx(
        headingStyles({
          variant,
          weight,
          center,
          color,
        }),
        className,
      )}
      {...props}
    >
      {children}
    </HeadingTag>
  );
};
