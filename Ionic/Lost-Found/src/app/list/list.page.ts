import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { NavController } from '@ionic/angular';
import { FormsModule } from '@angular/forms'; // Import FormsModule for ngModel

interface Item {
  image_path: string;
  item_name: string;
  category: string;
  description: string;
  date_found: string;
  finder_name: string;
  finder_contact: string;
}

@Component({
  selector: 'app-list',
  templateUrl: './list.page.html',
  styleUrls: ['./list.page.scss'],
  standalone: true,
  imports: [CommonModule, IonicModule, HttpClientModule, FormsModule] // Add FormsModule here
})
export class ListPage implements OnInit {
  paginatedItems: Item[] = [];
  currentPage = 1;
  itemsPerPage = 10;
  totalPages = 1;
  items: Item[] = [];
  originalItems: Item[] = []; // Store original items
  searchTerm: string = ''; // Add search term property

  constructor(private http: HttpClient, private navCtrl: NavController) {}

  ngOnInit() {
    this.checkLogin();
    this.loadItems();
  }

  ionViewWillEnter() {
    this.checkLogin();
  }

  checkLogin() {
    if (!this.isLoggedIn()) {
      this.navCtrl.navigateRoot('/home');
    }
  }

  isLoggedIn(): boolean {
    const username = localStorage.getItem('username');
    const email = localStorage.getItem('email');
    return username !== null && email !== null;
  }

  updatePaginatedItems() {
    const startIndex = (this.currentPage - 1) * this.itemsPerPage;
    const endIndex = startIndex + this.itemsPerPage;
    this.paginatedItems = this.items.slice(startIndex, endIndex);
  }

  nextPage() {
    if (this.currentPage < this.totalPages) {
      this.currentPage++;
      this.updatePaginatedItems();
    }
  }

  previousPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.updatePaginatedItems();
    }
  }

  loadItems() {
    this.http.get<Item[]>('http://localhost/getItem.php').subscribe(
      (response) => {
        this.originalItems = response; // Store the original items
        this.items = [...this.originalItems]; // Initialize items with the original list
        this.items.forEach(item => {
          if (item.image_path) {
            item.image_path = `http://localhost/${item.image_path}`;
          }
        });
        this.totalPages = Math.ceil(this.items.length / this.itemsPerPage);
        this.updatePaginatedItems();
      },
      (error) => {
        console.error('Error fetching items:', error);
      }
    );
  }

  filterItems() {
    if (this.searchTerm.trim() === '') {
      // If search term is empty, reset items to originalItems
      this.items = [...this.originalItems];
    } else {
      const lowercasedSearchTerm = this.searchTerm.toLowerCase();
      this.items = this.originalItems.filter(item =>
        item.item_name.toLowerCase().includes(lowercasedSearchTerm) ||
        item.category.toLowerCase().includes(lowercasedSearchTerm)
      );
    }
    this.totalPages = Math.ceil(this.items.length / this.itemsPerPage);
    this.currentPage = 1; // Reset to first page
    this.updatePaginatedItems();
  }
}
