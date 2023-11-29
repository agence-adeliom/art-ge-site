import React, { FunctionComponent } from 'react';
import { cx } from 'class-variance-authority';
import { TextProps } from '@components/Typography/Text/Text.types';
import { textStyles } from '@components/Typography/Text/Text.styles';

export const Text: FunctionComponent<TextProps> = ({
  children,
  as: TextTag = 'p',
  size,
  weight,
  center,
  color,
  className,
  ...props
}) => {
  return (
    <TextTag
      className={cx(textStyles({ size, weight, center, color }), className)}
      {...props}
    >
      {children}
    </TextTag>
  );
};
