import React, { FunctionComponent } from 'react';
import { cx } from 'class-variance-authority';
import { IconProps } from '@components/Typography/Icon/Icon.types';
import { iconStyles } from '@components/Typography/Icon/Icon.styles';
// import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

export const Icon: FunctionComponent<IconProps> = ({
  icon,
  size,
  color,
  className,
  variant,
}) => {
  const renderIcon = () => {
    return (
      <i
        className={cx(iconStyles({ variant, size, color }), icon, className)}
      />
    );
  };

  return renderIcon();
};
