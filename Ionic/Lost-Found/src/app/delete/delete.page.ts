import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import {
  IonContent,
  IonHeader,
  IonTitle,
  IonToolbar,
  IonButtons,
  IonBackButton,
  IonButton,
  IonIcon,
  IonList,
  IonItem,
  IonThumbnail,
  IonLabel,
  IonFooter,
} from '@ionic/angular/standalone';

@Component({
  selector: 'app-delete',
  templateUrl: './delete.page.html',
  styleUrls: ['./delete.page.scss'],
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonButtons,
    IonBackButton,
    IonButton,
    IonIcon,
    HttpClientModule,
    IonList,
    IonItem,
    IonThumbnail,
    IonLabel,
    IonFooter,
  ],
})
export class DeletePage implements OnInit {
  items: any[] = [];
  paginatedItems: any[] = [];
  currentPage: number = 1;
  itemsPerPage: number = 10;
  totalPages: number = 0;

  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.fetchItems();
  }
  
  fetchItems() {
    const email = localStorage.getItem('email');
    this.http.get<any[]>(`http://localhost/getItem2.php?email=${email}`).subscribe(
      (data) => {
        console.log('Items fetched:', data); // Debugging line
        this.items = data.map(item => ({
          ...item,
          image_path: `http://localhost/${item.image_path}` // Ensure the path is correct
        }));
        this.totalPages = Math.ceil(this.items.length / this.itemsPerPage);
        this.paginateItems();
      },
      (error) => {
        console.error('Error fetching items:', error);
      }
    );
  }

  paginateItems() {
    const start = (this.currentPage - 1) * this.itemsPerPage;
    const end = start + this.itemsPerPage;
    this.paginatedItems = this.items.slice(start, end);
    console.log('Paginated items:', this.paginatedItems); // Debugging line
  }

  previousPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.paginateItems();
    }
  }

  nextPage() {
    if (this.currentPage < this.totalPages) {
      this.currentPage++;
      this.paginateItems();
    }
  }

  deleteItem(itemId: string) {
    this.http.post('http://localhost/processDelete.php', { id: itemId }).subscribe(
      (response: any) => {
        console.log('Item deleted:', response); // Debugging line
        this.fetchItems();
      },
      (error) => {
        console.error('Error deleting item:', error);
      }
    );
  }
}
