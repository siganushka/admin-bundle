import { Application } from '@hotwired/stimulus';
import theme from './controllers/theme.js'
import collapse from './controllers/collapse.js'

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.min.css';
import './styles/bootstrap5.css';

const app = Application.start();
app.register('siganushka-admin-theme', theme);
app.register('siganushka-admin-collapse', collapse);
