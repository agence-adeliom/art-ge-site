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
  raw,
  ...props
}) => {
    const options = {};
    if (raw === true) {
        // @ts-ignore
        options.dangerouslySetInnerHTML = {__html: children};
    } else {
        // @ts-ignore
        options.children = children;
    }
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
      {...options}
      {...props}
    >
    </HeadingTag>
  );
};
