import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { HttpClient, HttpClientModule } from '@angular/common/http';

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
  imports: [CommonModule, IonicModule, HttpClientModule]
})
export class ListPage implements OnInit {
  paginatedItems: Item[] = [];
  currentPage = 1;
  itemsPerPage = 10;
  totalPages = 1;
  items: Item[] = [];

  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.loadItems();
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
    this.http.get<Item[]>('http://192.168.155.169/getItem.php').subscribe(
      (response) => {
        this.items = response;
        this.items.forEach(item => {
          if (item.image_path) {
            item.image_path = `http://192.168.155.169/${item.image_path}`;
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
}
