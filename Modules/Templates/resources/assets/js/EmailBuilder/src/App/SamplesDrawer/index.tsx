import React, { useEffect } from 'react';
import { Box, Button, Divider, Drawer, Link, Stack, Typography } from '@mui/material';

import { useSamplesDrawerOpen } from '../../documents/editor/EditorContext';

import SidebarButton from './SidebarButton';
import { resetDocument } from '../../documents/editor/EditorContext';
import SidebarLoadTemplateButton from './SidebarLoadTemplateButton';

const logo = 'https://www.Email Builder.com/index/assets/images/logo.svg';

export const SAMPLES_DRAWER_WIDTH = 240;

type Template = {
  id: number;
  name: string;
  content: { json: any };
};

type Props = {
  templates: Template[];
};

export default function SamplesDrawer({ templates }: Props) {
  const samplesDrawerOpen = useSamplesDrawerOpen();

  const handleClick = (id: number) => {
    const template = templates.find((temp) => temp.id === id);
    if (template) {
      resetDocument(template.content.json);
    }
  };
  // useEffect hook to monitor changes in the URL hash
  useEffect(() => {
    // alert("dmkwe");
    const handleHashChange = () => {
      // Extract the template ID from the URL hash
      const hash = window.location.hash;
      const match = hash.match(/^#template\/(\d+)$/);
      if (match) {
        const templateId = parseInt(match[1], 10);
        // Find the corresponding template
        const template = templates.find((temp) => temp.id === templateId);
        if (template) {
          resetDocument(template.content.json);
        }
      }
    };

    // Initialize the effect by checking the current hash
    handleHashChange();

    // Add event listener for hash changes
    window.addEventListener('hashchange', handleHashChange);

    // Cleanup the event listener on component unmount
    return () => {
      window.removeEventListener('hashchange', handleHashChange);
    };
  }, [templates]);

  return (
    <Drawer
      variant="persistent"
      anchor="left"
      open={samplesDrawerOpen}
      sx={{
        width: samplesDrawerOpen ? SAMPLES_DRAWER_WIDTH : 0,
      }}
    >
      <Stack spacing={3} py={1} px={2} width={SAMPLES_DRAWER_WIDTH} justifyContent="space-between" height="100%">
        <Stack spacing={2} sx={{ '& .MuiButtonBase-root': { width: '100%', justifyContent: 'flex-start' } }}>
          <Typography variant="h6" component="h1" sx={{ p: 0.75 }}>
            Email Builder Email Builder
          </Typography>

          <Stack alignItems="flex-start">
            <SidebarButton href="#">Empty</SidebarButton>
            {templates.map((template) => (
              <Button
                size="small"
                key={template.id}
                href={`#template/${template.id}`}
                onClick={() => handleClick(template.id)}
              >
                {template.name}
              </Button>
            ))}
          </Stack>

          <Divider />
          <Stack alignItems="flex-start">
            <SidebarLoadTemplateButton templateName="header_footer">Add Header Footer</SidebarLoadTemplateButton>
          </Stack>
        </Stack>

        <Stack spacing={2} px={0.75} py={3}>
          <Link href="https://www.Email Builder.com" target="_blank" sx={{ lineHeight: 1 }}>
            <Box component="img" src={logo} width={64} />
          </Link>
          <Box>
            <Typography variant="overline" gutterBottom>
              Email Builder Email Builder
            </Typography>
          </Box>
        </Stack>
      </Stack>
    </Drawer>
  );
}
