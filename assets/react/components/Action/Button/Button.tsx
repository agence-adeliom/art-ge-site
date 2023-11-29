import React, { FunctionComponent } from 'react';
import { Link } from 'react-router-dom';
import { cx } from 'class-variance-authority';
import { ButtonProps } from '@components/Action/Button/Button.types';
import { buttonStyles } from '@components/Action/Button/Button.styles';
import { Icon } from '@components/Typography/Icon';

export const Button: FunctionComponent<ButtonProps> = ({
  children,
  variant,
  size,
  icon,
  iconSide,
  href,
  disabled,
  loading,
  className,
  weight,
  ...props
}) => {
  const isLink = typeof href !== 'undefined';
  const classNames = cx(
    buttonStyles({
      variant,
      size,
      iconSide,
      disabled: disabled || loading,
      loading,
      weight,
    }),
    className
  );

  const content = (
    <>
      {children}
      {icon && !loading ? <Icon icon={icon} size="xs" /> : null}
      {loading ? (
        <Icon
          icon="spinner-third"
          size="lg"
          variant="duotone"
          className="animate-spin"
        />
      ) : null}
    </>
  );

  if (isLink && !(disabled || loading)) {
    return (
      <Link
        className={classNames}
        to={href}
        aria-label={props['aria-label'] ?? (children as string)}
        {...props}
      >
        {content}
      </Link>
    );
  }

  return (
    <button
      disabled={disabled || loading}
      className={classNames}
      aria-label={props['aria-label'] ?? (children as string)}
      {...props}
    >
      {content}
    </button>
  );
};


 