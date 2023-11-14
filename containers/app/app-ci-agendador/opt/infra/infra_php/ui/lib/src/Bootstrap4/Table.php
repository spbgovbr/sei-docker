<?php


namespace TRF4\UI\Bootstrap4;


class Table extends \TRF4\UI\Component\Table
{
    public function __construct(?string $title, array $rows)
    {

        parent::__construct($title, $rows);
        $this->_table->class('table table-hover table-sm');
    }

    public function render(): string
    {
        if ($this->hasRows()) {

            $header = $this->buildHeader();
            $rows = $this->buildBody();

            $this->_table->innerHTML($header . $rows);

            $result = <<<html
				<div class="card shadow-sm">
					<div class="card-body">
						<h5>$this->title</h5>
						<div class="table-responsive table-striped">
							$this->_table					
						</div>
					</div>
				</div>
html;
        } else {
            $result = '';
        }

        return $result;
    }

    protected function hasRows()
    {
        return $this->rows && count($this->rows) > 0;
    }


    protected function buildHeader()
    {
        $html = '';

        if ($this->withHeader) {
            $html = '<thead><tr>';
            $html .= $this->buildDefaultHeaderColumns();
            $html .= $this->buildAdditionalHeaderColumns();
            $html .= '</tr></thead>';
        }

        return $html;
    }

    protected function buildDefaultHeaderColumns(): string
    {
        $html = '';
        foreach ($this->getColumnNames() as $column) {
            $html .= $this->buildHeaderColumn($column);
        }
        return $html;
    }

    /**
     * @return string[]
     */
    protected function getColumnNames(): array
    {
        $columns = array_keys($this->rows[array_keys($this->rows)[0]]);
        return $columns;
    }

    protected function buildHeaderColumn(string $column): string
    {
        return "<th class=\"font-weight-bold\">$column</th>";
    }

    protected function buildAdditionalHeaderColumns(): string
    {
        $html = '';
        foreach (array_keys($this->additionalColumns) as $c) {
            $html .= $this->buildHeaderColumn($c);
        }
        return $html;
    }

    protected function buildBody(): string
    {
        $html = '<tbody>';
        foreach ($this->rows as $row) {
            $html .= '<tr>';
            $html .= $this->buildDefaultBodyColumns($row);
            $html .= $this->buildAdditionalBodyColumns($row);
            $html .= '</tr>';
        }
        $html .= '</tbody>';

        return $html;
    }

    private function buildDefaultBodyColumns(array $row)
    {
        $html = '';
        foreach (array_values($row) as $value) {
            $html .= $this->buildBodyColumn($value);
        }
        return $html;
    }

    protected function buildBodyColumn($value): string
    {
        return "<td>$value</td>";
    }

    private function buildAdditionalBodyColumns(array $row)
    {
        $html = '';
        foreach (array_values($this->additionalColumns) as $fn) {
            $html .= $this->buildBodyColumn($fn($row));
        }
        return $html;
    }


}