import React from 'react';
import { CssBaseline, ThemeProvider } from '@mui/material';

import App from '../EmailBuilder/src/App';
import theme from '../EmailBuilder/src/theme';

export default function EmailBuilder(inertiaProps) {
    return (
        <ThemeProvider theme={theme}>
            <CssBaseline />
            <App inertiaProps={inertiaProps} />
        </ThemeProvider>
    );
}
