import React, { ReactElement, FunctionComponent } from 'react';
import { TextInputProps } from '@components/Fields/TextInput';
import { Fields } from '@react/types/Fields';
import { useController } from 'react-hook-form';
import { cx } from 'class-variance-authority';
import { Error } from '@components/Fields/Error';

const inputClass: string =
  'border-0 border-b border-neutral-500 block w-full mt-4 pb-2 ring-0 outline-none focus:ring-0 focus:border-secondary-200 trans-default';
export const TextInput: FunctionComponent<TextInputProps> = ({
  label,
  name,
  placeholder,
  type = Fields.TEXT,
  control,
  defaultValue = '',
  required = false,
  disabled = false,
  containerClass,
  autoCompleteChoice,
  onChange,
  onBlur,
  onClick,
  onFocus,
}) => {
  const { field, fieldState } = useController({ name, control, defaultValue });

  const handleBlur = () => {
    onBlur && onBlur();
  };

  const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    onChange && onChange(event);
  };
  const handleClick = (event: React.MouseEvent<HTMLInputElement>) => {
    onClick && onClick(event);
  };

  const handleFocus = (event: React.FocusEvent<HTMLInputElement>) => {
    onFocus && onFocus(event);
  };

  return (
    <div className={containerClass}>
      {label && (
        <label className={cx('mb-2')} htmlFor={name}>
          {label}
        </label>
      )}

      <input
        className={inputClass}
        placeholder={placeholder}
        type={type}
        id={name}
        autoComplete={autoCompleteChoice === false ? 'off' : 'on'}
        aria-invalid={fieldState.error ? 'true' : 'false'}
        onClick={event => {
          handleClick(event);
        }}
        onFocus={event => {
          handleFocus(event);
        }}
        {...field}
        onChange={event => {
          field.onChange(event);
          handleChange(event);
        }}
        onBlur={() => {
          field.onBlur();
          handleBlur();
        }}
      ></input>
      {fieldState.error && <Error message={fieldState.error.message!} />}
    </div>
  );
};
