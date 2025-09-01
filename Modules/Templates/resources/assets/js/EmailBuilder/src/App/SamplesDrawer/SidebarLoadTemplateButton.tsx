import React from 'react';

import { Button } from '@mui/material';

import { resetDocument } from '../../documents/editor/EditorContext';
import getTemplates from '../../getTemplates/index';

export default function SidebarLoadTemplateButton({ templateName, children }: { templateName: string; children: JSX.Element | string }) {
  const handleClick = () => {

   console.log(getTemplates(templateName));
   //Add configuration to resetDocument
    resetDocument(getTemplates(templateName));
  };
  return (
    <Button size="small" onClick={handleClick}>
      {children}
    </Button>
  );
}
