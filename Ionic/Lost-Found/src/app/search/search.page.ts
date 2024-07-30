import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { HttpClient, HttpClientModule } from '@angular/common/http';

interface Item {
  id: string;
  item_name: string;
  category: string;
  date_found: string;
  description: string;
  finder_name: string;
  finder_contact: string;
  image_path: string;
}

@Component({
  selector: 'app-search',
  templateUrl: './search.page.html',
  styleUrls: ['./search.page.scss'],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule, HttpClientModule],
})
export class SearchPage implements OnInit {
  searchName: string = '';
  searchCategory: string = '';
  records: Item[] = [];
  message: string = '';

  constructor(private http: HttpClient) {}

  ngOnInit() {}

  search() {
    if (!this.searchName && !this.searchCategory) {
      this.records = [];
      this.message = 'Please enter a search term.';
      return;
    }

    let url = `http://localhost/searchItems.php?`;
    if (this.searchName) {
      url += `name=${encodeURIComponent(this.searchName)}`;
    } else if (this.searchCategory) {
      url += `category=${encodeURIComponent(this.searchCategory)}`;
    }

    this.http.get<Item[]>(url).subscribe(
      (data) => {
        console.log('Items fetched:', data);
        this.records = data.map(item => ({
          ...item,
          item_name: item.item_name || 'No Name', // Add defaults for missing values if necessary
          category: item.category || 'No Category',
          date_found: item.date_found || 'No Date',
          description: item.description || 'No Description',
          finder_name: item.finder_name || 'No Finder Name',
          finder_contact: item.finder_contact || 'No Finder Contact',
          image_path: item.image_path ? `http://localhost/${item.image_path}` : null,
        }));
        this.message = this.records.length > 0 ? '' : 'No records found.';
      },
      (error) => {
        console.error('Error fetching data:', error);
        this.message = 'Error fetching data. Please try again later.';
      }
    );
  }
}
