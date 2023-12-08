import { BaseFieldProps, Fields } from '@react/types/Fields';
import React from 'react';

export interface TextInputProps extends BaseFieldProps {
  type: Fields;
  onBlur?: () => void;
  onClick?: (e: React.MouseEvent<HTMLInputElement>) => void;
  onFocus?: (e: React.FocusEvent<HTMLInputElement>) => void;
}
